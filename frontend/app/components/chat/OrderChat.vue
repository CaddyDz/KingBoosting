<script setup lang="ts">
import { mdiAlertCircleOutline, mdiSend } from '@mdi/js'
import { confirmMessage, markReadUpTo, mergeMessages, type ChatEntry } from '~/utils/chat'
import { formatDateTime } from '~/utils/orders'
import type { ChatMessage, Order, Wrapped } from '~/types/api'

const props = defineProps<{ order: Order }>()

const api = useApi()
const { user, role } = useUser()
const messages = ref<ChatEntry[]>([])
const hasMore = ref(false)
const before = ref<number | null>(null)
const loadingOlder = ref(false)
const draft = ref('')
const list = ref<HTMLElement | null>(null)
let tempCounter = 0

const counterpart = computed(() => role.value === 'customer' ? 'your booster and support' : role.value === 'booster' ? 'the customer and support' : 'the customer and the booster')
const lastOwnId = computed(() => [...messages.value].reverse().find(entry => entry.sender?.id === user.value?.id)?.id)

function scrollToBottom(): void {
	nextTick(() => list.value?.scrollTo({ top: list.value.scrollHeight }))
}

async function loadPage(): Promise<ChatMessage[]> {
	const response = await api<{ data: ChatMessage[], meta: { has_more: boolean, before: number | null } }>(`/api/orders/${props.order.id}/messages`, {
		query: before.value ? { before: before.value } : {},
	})
	hasMore.value = response.meta.has_more
	before.value = response.meta.before

	return response.data
}

// Infinite scroll upwards: near the top, load the page before, and keep the reader where they were
async function onScroll(): Promise<void> {
	const element = list.value
	if (!element || element.scrollTop > 60 || !hasMore.value || loadingOlder.value) {
		return
	}
	loadingOlder.value = true
	const height = element.scrollHeight
	messages.value = mergeMessages(messages.value, await loadPage())
	await nextTick()
	element.scrollTop = element.scrollHeight - height
	loadingOlder.value = false
}

let readTimer: ReturnType<typeof setTimeout> | undefined
function markRead(): void {
	clearTimeout(readTimer)
	readTimer = setTimeout(() => {
		const unread = messages.value.some(entry => !entry.read_at && entry.sender?.id !== user.value?.id && entry.state === 'sent')
		if (unread && document.visibilityState === 'visible') {
			api(`/api/orders/${props.order.id}/messages/read`, { method: 'POST' })
		}
	}, 400)
}

async function send(): Promise<void> {
	const body = draft.value.trim()
	if (!body || !user.value) {
		return
	}
	draft.value = ''
	const tempId = `pending-${++tempCounter}`
	messages.value = [...messages.value, {
		id: tempId,
		order_id: props.order.id,
		body,
		system: false,
		sender: { id: user.value.id, name: user.value.username, role: user.value.role },
		read_at: null,
		created_at: new Date().toISOString(),
		state: 'sending',
	}]
	scrollToBottom()
	await deliver(tempId, body)
}

async function deliver(tempId: string, body: string): Promise<void> {
	try {
		const saved = await api<Wrapped<ChatMessage>>(`/api/orders/${props.order.id}/messages`, {
			method: 'POST',
			body: { body },
			// Laravel's toOthers() skips this socket, so the message doesn't come back to this tab.
			// Without a socket yet, send no header: an empty one makes the broadcast fail.
			headers: socketHeader(),
		})
		messages.value = confirmMessage(messages.value, tempId, saved.data)
	}
	catch {
		messages.value = messages.value.map(entry => entry.id === tempId ? { ...entry, state: 'failed' } : entry)
	}
}

function socketHeader(): Record<string, string> {
	const socketId = useEcho().socketId()

	return socketId ? { 'X-Socket-ID': socketId } : {}
}

function retry(entry: ChatEntry): void {
	messages.value = messages.value.map(item => item.id === entry.id ? { ...item, state: 'sending' } : item)
	deliver(String(entry.id), entry.body)
}

function onEnter(event: KeyboardEvent): void {
	if (!event.shiftKey) {
		event.preventDefault()
		send()
	}
}

onMounted(async () => {
	messages.value = mergeMessages([], await loadPage())
	scrollToBottom()
	markRead()

	useEcho().private(`orders.${props.order.id}`)
		.listen('.message.sent', (message: ChatMessage) => {
			const atBottom = !list.value || list.value.scrollHeight - list.value.scrollTop - list.value.clientHeight < 80
			messages.value = mergeMessages(messages.value, [message])
			if (atBottom) {
				scrollToBottom()
			}
			markRead()
		})
		.listen('.messages.read', (event: { reader_id: number, up_to_id: number }) => {
			messages.value = markReadUpTo(messages.value, event.reader_id, event.up_to_id, new Date().toISOString())
		})
})
</script>

<template>
	<v-card class="d-flex flex-column" data-testid="chat">
		<v-card-item title="Chat">
			<template #subtitle>
				<span class="text-wrap">With {{ counterpart }}. Never share your password here.</span>
			</template>
		</v-card-item>
		<v-divider />
		<div ref="list" class="chat-list pa-3" data-testid="chat-messages" @scroll.passive="onScroll">
			<div v-if="hasMore" class="text-center text-body-small text-medium-emphasis py-2">
				{{ loadingOlder ? 'Loading…' : 'Scroll up for older messages' }}
			</div>
			<template v-for="entry in messages" :key="entry.id">
				<div v-if="entry.system" class="text-center my-3" data-testid="chat-system">
					<span class="system-line text-body-small px-3 py-1">{{ entry.body }}</span>
				</div>
				<div v-else class="d-flex flex-column my-2" :class="entry.sender?.id === user?.id ? 'align-end' : 'align-start'">
					<span v-if="entry.sender?.id !== user?.id" class="text-body-small text-medium-emphasis mb-1">{{ entry.sender?.name }}</span>
					<div class="bubble px-3 py-2" :class="entry.sender?.id === user?.id ? 'bubble--mine' : 'bubble--theirs'" data-testid="chat-message">
						{{ entry.body }}
					</div>
					<span class="text-body-small text-medium-emphasis mt-1">
						<template v-if="entry.state === 'sending'">Sending…</template>
						<v-btn v-else-if="entry.state === 'failed'" variant="text" size="x-small" color="error" :prepend-icon="mdiAlertCircleOutline" @click="retry(entry)">Not sent, retry</v-btn>
						<template v-else>
							{{ formatDateTime(entry.created_at) }}<template v-if="entry.id === lastOwnId && entry.read_at"> · Seen</template>
						</template>
					</span>
				</div>
			</template>
			<div v-if="!messages.length" class="text-center text-medium-emphasis py-8">
				No messages yet.
			</div>
		</div>
		<v-divider />
		<div class="d-flex align-end ga-2 pa-3">
			<v-textarea v-model="draft" placeholder="Write a message" rows="1" auto-grow max-rows="5" hide-details density="compact" data-testid="chat-input" @keydown.enter="onEnter" />
			<v-btn :icon="mdiSend" color="primary" :disabled="!draft.trim()" aria-label="Send" data-testid="chat-send" @click="send" />
		</div>
	</v-card>
</template>

<style scoped>
.chat-list {
	height: 460px;
	overflow-y: auto;
}

.bubble {
	max-width: 85%;
	border-radius: 14px;
	white-space: pre-wrap;
	word-break: break-word;
}

.bubble--mine {
	background: rgb(var(--v-theme-primary));
	color: rgb(var(--v-theme-on-primary));
	border-bottom-right-radius: 4px;
}

.bubble--theirs {
	background: rgb(var(--v-theme-surface-variant));
	border-bottom-left-radius: 4px;
}

.system-line {
	border-radius: 999px;
	background: rgba(var(--v-theme-on-surface), 0.06);
	color: rgba(var(--v-theme-on-surface), 0.7);
}
</style>

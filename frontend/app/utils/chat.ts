import type { ChatMessage } from '~/types/api'

/**
 * A message as the chat panel shows it: the server's, or one still on its way.
 */
export interface ChatEntry extends Omit<ChatMessage, 'id'> {
	id: number | string
	state: 'sent' | 'sending' | 'failed'
}

/**
 * Add messages to the conversation, oldest first, without duplicates: the same message can come back
 * from the POST, from the socket (another tab) and from a reload.
 */
export function mergeMessages(current: ChatEntry[], incoming: ChatMessage[]): ChatEntry[] {
	const known = new Set(current.map(entry => entry.id))
	const added = incoming.filter(message => !known.has(message.id)).map(message => ({ ...message, state: 'sent' as const }))

	return [...current, ...added].sort((a, b) => {
		// Messages still sending stay at the bottom, in the order they were typed
		if (typeof a.id === 'string' || typeof b.id === 'string') {
			return typeof a.id === 'string' ? (typeof b.id === 'string' ? 0 : 1) : -1
		}

		return a.id - b.id
	})
}

/**
 * Swap a pending message for the server's copy once the POST answers.
 */
export function confirmMessage(current: ChatEntry[], tempId: string, saved: ChatMessage): ChatEntry[] {
	const withoutTemp = current.filter(entry => entry.id !== tempId)

	return mergeMessages(withoutTemp, [saved])
}

/**
 * The other side read everything up to a message: mark ours as read.
 */
export function markReadUpTo(current: ChatEntry[], readerId: number, upToId: number, at: string): ChatEntry[] {
	return current.map(entry => (typeof entry.id === 'number' && entry.id <= upToId && entry.sender?.id !== readerId && !entry.read_at)
		? { ...entry, read_at: at }
		: entry)
}

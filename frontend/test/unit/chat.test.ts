import { describe, expect, it } from 'vitest'
import { confirmMessage, markReadUpTo, mergeMessages, type ChatEntry } from '../../app/utils/chat'
import type { ChatMessage } from '../../app/types/api'

const message = (id: number, senderId = 1, readAt: string | null = null): ChatMessage => ({
	id,
	order_id: 7,
	body: `message ${id}`,
	system: false,
	sender: { id: senderId, name: `user${senderId}`, role: 'customer' },
	read_at: readAt,
	created_at: '2026-10-17T12:00:00Z',
})
const ids = (entries: ChatEntry[]) => entries.map(entry => entry.id)

describe('mergeMessages', () => {
	it('keeps the conversation in order without duplicates', () => {
		const merged = mergeMessages(mergeMessages([], [message(3), message(4)]), [message(1), message(4), message(2)])

		expect(ids(merged)).toEqual([1, 2, 3, 4])
	})

	it('keeps messages still sending at the bottom', () => {
		const pending: ChatEntry = { ...message(0), id: 'pending-1', state: 'sending' }

		expect(ids(mergeMessages([pending], [message(5)]))).toEqual([5, 'pending-1'])
	})
})

describe('confirmMessage', () => {
	it('swaps the pending message for the saved one', () => {
		const pending: ChatEntry = { ...message(0), id: 'pending-1', state: 'sending' }
		const confirmed = confirmMessage(mergeMessages([pending], [message(4)]), 'pending-1', message(9))

		expect(ids(confirmed)).toEqual([4, 9])
		expect(confirmed.at(-1)?.state).toBe('sent')
	})

	it('doesn’t duplicate a message the socket already delivered', () => {
		const pending: ChatEntry = { ...message(0), id: 'pending-1', state: 'sending' }

		expect(ids(confirmMessage(mergeMessages([pending], [message(9)]), 'pending-1', message(9)))).toEqual([9])
	})
})

describe('markReadUpTo', () => {
	it('marks what the reader didn’t send, up to the message they read', () => {
		const entries = mergeMessages([], [message(1, 1), message(2, 2), message(3, 1)])
		const read = markReadUpTo(entries, 2, 2, 'now')

		expect(read.map(entry => entry.read_at)).toEqual(['now', null, null])
	})
})

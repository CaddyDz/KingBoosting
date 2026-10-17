/**
 * Listen to a document event while the component is mounted.
 */
export function useEventListener(event: string, handler: () => void): void {
	onMounted(() => document.addEventListener(event, handler))
	onBeforeUnmount(() => document.removeEventListener(event, handler))
}

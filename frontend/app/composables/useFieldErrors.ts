/**
 * A form's validation errors from Laravel, as field => message. A field's message clears as soon as its value
 * changes, so a corrected field stops looking wrong before the next submit.
 */
export function useFieldErrors<T extends object>(form: T) {
	const errors = ref<Record<string, string>>({})

	watch(() => ({ ...form }), (now, before) => {
		const changed = (Object.keys(now) as (keyof T & string)[]).filter(key => now[key] !== before[key])
		if (changed.some(key => key in errors.value)) {
			errors.value = Object.fromEntries(Object.entries(errors.value).filter(([key]) => !changed.includes(key as keyof T & string)))
		}
	})

	return errors
}

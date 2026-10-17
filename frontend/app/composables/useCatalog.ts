import type { CatalogGame } from '~/types/api'

/**
 * The games and services the store sells, for labels and filters. Fetched once per page load.
 */
export function useCatalog() {
	const { data } = useAsyncData('catalog', async () => (await useApi()<{ data: CatalogGame[] }>('/api/catalog')).data, {
		default: () => [] as CatalogGame[],
		dedupe: 'defer',
	})

	const serviceLabel = (key: string): string => {
		for (const game of data.value) {
			const service = game.services.find(service => service.key === key)
			if (service) {
				return service.label
			}
		}

		return key
	}

	return { games: data, serviceLabel }
}

export default (context, inject) => {
	const range = (start, end) => _.range(start + 1, end + 1).filter(() => start <= end);
	inject('range', range);
}

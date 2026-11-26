// MySQL returns "YYYY-MM-DD HH:MM:SS". Hermes (the React Native JS engine) and
// Safari can't parse the space form and give Invalid Date, so normalise to ISO.
export function parseDbDate(value) {
  if (!value) return new Date(NaN);
  return new Date(String(value).replace(' ', 'T'));
}

export function formatDbDate(value) {
  const date = parseDbDate(value);
  return Number.isNaN(date.getTime()) ? String(value) : date.toLocaleString();
}

// Helpers genéricos
export const qs  = (sel, root = document) => root.querySelector(sel);
export const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));
export const money = (n) => `$${(+n || 0).toFixed(2)}`;
export const getCsrf = () =>
  (window.Store?.Rifa?.csrf) ||
  (qs('meta[name="csrf-token"]')?.getAttribute('content') ?? '');

export function firstById(...ids) {
  for (const id of ids) {
    const el = id ? qs(`#${id}`) : null;
    if (el) return el;
  }
  return null;
}
export function debounce(fn, wait = 200){
  let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
}

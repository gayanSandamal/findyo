export interface IRating {
  id: number,
  cid: number,
  question?: string | null,
  weight?: string | number,
  active: number,
  order: number
}

export interface IRatingHeader {
  text: string,
  value: string,
  align?: string| null,
  width?: string | number | null,
  sortable?: boolean | null
}

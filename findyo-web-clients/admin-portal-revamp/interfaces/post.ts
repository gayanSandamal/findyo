export interface IPost {
  id: number,
  verified: boolean,
  likeCount: number,
  likes?: null | number | string,
  shareCount: number,
  shares: null | number | string,
  userId: number,
  categoryId?: number | null,
  locationId?: number | null,
  postContent?: string | null,
  postType: 'consumer' | 'seller',
  cid?: string | null,
}

export interface IPostRejectReason {
  value: number,
  text: string
}

export interface IPostTableItem extends IPost {
  postContentShort: string,
  location: string,
  verifiedText: string
}

export interface ITableHeaders {
  text: string,
  value: string,
  align?: string,
  sortable?: boolean,
  width?: number | string,
}

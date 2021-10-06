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

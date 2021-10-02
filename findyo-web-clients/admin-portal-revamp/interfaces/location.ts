export interface ILocationLevel {
  id: number,
  name?: string,
  cid?: string | null,
  disabled: boolean,
}

export interface ILocation {
  id: number,
  name: string,
  parent?: number | null,
  locationLevelId?: number | null,
  cid?: string,
  disabled?: boolean
}

interface ICategoryTreeChild {
  id: number,
  name: string,
  children?: ICategoryTreeChild[]
}

export interface ILocationTreeItem extends ILocation {
  children: ICategoryTreeChild[]
}

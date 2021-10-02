export interface ILocation {
  id: number,
  name: string,
  parent?: number,
  locationLevelId?: number,
  cid?: string
}

export interface ILocationLevel {
  id: number,
  name?: string,
  cid: string,
  text?: string,
  disabled: boolean,
  value: number
}

interface ILocationTreeChild {
  id: number,
  name: string,
  children?: ILocationTreeChild[]
}

export interface ILocationTreeItem extends ILocation {
  children: ILocationTreeChild[]
}

export interface ICategory {
  id: number,
  name: string,
  parent?: number|null,
  cid?: string,
  disabled?: boolean|null,
}

interface ICategoryTreeChild {
  id: number,
  name: string,
  children?: ICategoryTreeChild[]
}

export interface ICategoryTreeItem extends ICategory {
  children: ICategoryTreeChild[]
}

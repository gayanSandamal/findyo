export interface ICategory {
  id: number,
  name: string,
  parent?: number|null,
  cid?: string
}

interface ICategoryTreeChild {
  id: number,
  name: string,
  children?: ICategoryTreeChild[]
}

export interface ICategoryTreeItem extends ICategory {
  children: ICategoryTreeChild[]
}

export interface ICategoryComboItem extends ICategory {
  text?: string,
  disabled: boolean,
  value: number
}

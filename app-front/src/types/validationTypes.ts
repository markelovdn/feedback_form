export interface Rule {
    value: (value: any) => boolean;
    message: string;
}

export type Errors = Record<string, string[]>;

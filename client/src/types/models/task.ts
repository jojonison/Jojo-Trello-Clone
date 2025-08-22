export type Task = {
    id?: number | never;
    title: string;
    description?: string;
    status?: string;
    created_at?: string | Date;
    completed_at?: string | Date;
}
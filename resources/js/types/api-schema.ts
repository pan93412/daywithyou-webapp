import { z } from 'zod/v4';

export const userResourceSchema = z.object({
    id: z.number(),
    name: z.string(),
    email: z.string(),
})

export const commentResourceSchema = z.object({
    id: z.number(),
    content: z.string(),
    star: z.number().min(1).max(5),
    user: userResourceSchema,
})

export const commentsResourceSchema = z.array(commentResourceSchema);

export const commentResourceDataSchema = z.object({
    data: commentsResourceSchema,
})

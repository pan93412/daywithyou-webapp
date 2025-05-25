import { formatRating } from '@/lib/utils';
import { Comment } from '@/types/resource';

export function ProductCommentsSkeleton() {
    return (
        <div className="space-y-6">
            <div className="mb-2 flex items-center gap-2">
                <div className="flex">
                    {[...Array(5)].map((_, i) => (
                        <div key={i} className="mr-1 h-5 w-5 rounded bg-gray-200"></div>
                    ))}
                </div>
                <div className="h-4 w-32 rounded bg-gray-200"></div>
            </div>

            {[...Array(3)].map((_, i) => (
                <div key={i} className="mb-6 space-y-2">
                    <div className="h-5 w-32 rounded bg-gray-200"></div>
                    <div className="flex">
                        {[...Array(5)].map((_, j) => (
                            <div key={j} className="mr-1 h-4 w-4 rounded bg-gray-200"></div>
                        ))}
                    </div>
                    <div className="h-4 w-full rounded bg-gray-200"></div>
                    <div className="h-4 w-3/4 rounded bg-gray-200"></div>
                </div>
            ))}
        </div>
    );
}

export interface ProductCommentsProps {
    comments: Comment[];
}

export default function ProductComments({ comments }: ProductCommentsProps) {
    const totalComments = comments.length;
    const averageRating = comments.reduce((total, comment) => total + comment.rating, 0) / totalComments;

    if (totalComments === 0) {
        return (
            <>
                <span className="text-zinc-600">目前還沒有評論</span>
            </>
        );
    }

    return (
        <>
            <div className="mb-2 flex items-center gap-2">
                <span className="text-xl text-yellow-500">{formatRating(averageRating)}</span>
                <span className="text-sm text-zinc-600">
                    {averageRating} / 5.0 ({totalComments} 則評價)
                </span>
            </div>
            {comments.map((comment) => (
                <div key={comment.id} className="mb-6">
                    <p className="font-medium text-zinc-800">{comment.user.name}</p>
                    <div className="text-base text-yellow-500">{formatRating(comment.rating)}</div>
                    <p className="text-sm text-zinc-600">{comment.content}</p>
                </div>
            ))}
        </>
    );
}

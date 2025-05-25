import { Comment } from '@/types/resource';

export function ProductCommentsSkeleton() {
    return (
        <div className="space-y-6">
            <div className="flex items-center gap-2 mb-2">
                <div className="flex">
                    {[...Array(5)].map((_, i) => (
                        <div key={i} className="w-5 h-5 bg-gray-200 rounded mr-1"></div>
                    ))}
                </div>
                <div className="h-4 w-32 bg-gray-200 rounded"></div>
            </div>
            
            {[...Array(3)].map((_, i) => (
                <div key={i} className="mb-6 space-y-2">
                    <div className="h-5 w-32 bg-gray-200 rounded"></div>
                    <div className="flex">
                        {[...Array(5)].map((_, j) => (
                            <div key={j} className="w-4 h-4 bg-gray-200 rounded mr-1"></div>
                        ))}
                    </div>
                    <div className="h-4 w-full bg-gray-200 rounded"></div>
                    <div className="h-4 w-3/4 bg-gray-200 rounded"></div>
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
    const averageRating = comments.reduce((total, comment) => total + comment.star, 0) / totalComments;

    if (totalComments === 0) {
        return (
            <>
                <span className="text-zinc-600">目前還沒有評論</span>
            </>
        );
    }

    return (
        <>
            <div className="flex items-center gap-2 mb-2">
                <span className="text-yellow-500 text-xl">{renderStar(averageRating)}</span>
                <span className="text-zinc-600 text-sm">{averageRating} / 5.0 ({totalComments} 則評價)</span>
            </div>
            {comments.map((comment) => (
                <div key={comment.id} className="mb-6">
                    <p className="text-zinc-800 font-medium">{comment.user.name}</p>
                    <div className="text-yellow-500 text-base">{renderStar(comment.star)}</div>
                    <p className="text-zinc-600 text-sm">{comment.content}</p>
                </div>
            ))}
        </>
    )
}

function renderStar(rating: number): string {
    return '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));
}

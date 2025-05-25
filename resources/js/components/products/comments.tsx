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

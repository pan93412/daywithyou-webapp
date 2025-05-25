import { NewsIndex, PaginatedData } from '@/types/resource';

interface Props {
    paginatedNewsData: PaginatedData<NewsIndex[]>;
}

export default function NewsList({ paginatedNewsData }: Props) {
    return (
        <div>
            <h1>News</h1>
            <pre>{JSON.stringify(paginatedNewsData, null, 2)}</pre>
        </div>
    );
}

import ProductCard from '@/components/product-card';
import { Button } from '@/components/ui/button';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { PaginatedData, ProductIndex } from '@/types/resource';
import { Link } from '@inertiajs/react';

interface Props {
    query?: string;
    productsReply: PaginatedData<ProductIndex[]>;
}

export default function ProductsList({ query, productsReply }: Props) {
    const title = query ? `「${query}」的搜尋結果` : '商品列表';

    return (
        <AppMainLayout title={title}>
            <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
                <h2 className="mb-8 text-center text-2xl font-bold">{title}</h2>

                {/* Products Grid */}
                <div className="mb-8 grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                    {productsReply.data.map((product) => (
                        <ProductCard
                            key={product.id}
                            figure={product.figure}
                            alt={product.name}
                            name={product.name}
                            summary={product.summary}
                            price={product.price}
                            id={product.id}
                        />
                    ))}
                </div>

                {/* Pagination Controls */}
                {productsReply.meta.last_page > 1 && (
                    <div className="mt-8 flex items-center justify-center gap-2">
                        {productsReply.meta.links.map((link, index) =>
                            link.url ? (
                                <Link key={index} href={link.url}>
                                    <Button variant={link.active ? 'default' : 'outline'} size="sm" className="min-w-[2.5rem]">
                                        {link.label.replace('&laquo;', '←').replace('&raquo;', '→')}
                                    </Button>
                                </Link>
                            ) : (
                                <span key={index} className="px-2 text-gray-400">
                                    {link.label.replace(/&laquo;|&raquo;/g, '')}
                                </span>
                            ),
                        )}
                    </div>
                )}

                <div className="mt-4 text-center text-sm text-gray-500">
                    顯示 {productsReply.meta.from} 至 {productsReply.meta.to} 筆，共 {productsReply.meta.total} 筆商品
                </div>
            </main>
        </AppMainLayout>
    );
}

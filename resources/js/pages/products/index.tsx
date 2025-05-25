import { PaginatedData, ProductIndex } from '@/types/resource';
import { AppHeader } from '@/components/app-header';
import { AppContent } from '@/components/app-content';
import ProductCard from '@/components/product-card';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface Props {
    paginatedProductsData: PaginatedData<ProductIndex[]>
}

export default function ProductsList({paginatedProductsData}: Props) {
    return (
        <AppContent>
            <AppHeader title="商品列表" />

            <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-8">
                <h2 className="mb-8 text-center text-2xl font-bold">所有商品</h2>

                {/* Products Grid */}
                <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 mb-8">
                    {paginatedProductsData.data.map((product) => (
                        <ProductCard
                            key={product.id}
                            image={product.image}
                            alt={product.name}
                            name={product.name}
                            description={product.description}
                            price={product.price}
                            id={product.id}
                        />
                    ))}
                </div>

                {/* Pagination Controls */}
                {paginatedProductsData.meta.last_page > 1 && (
                    <div className="flex justify-center items-center gap-2 mt-8">
                        {paginatedProductsData.meta.links.map((link, index) => (
                            link.url ? (
                                <Link
                                    key={index}
                                    href={link.url}
                                >
                                    <Button
                                        variant={link.active ? "default" : "outline"}
                                        size="sm"
                                        className="min-w-[2.5rem]"
                                    >
                                        {
                                            link.label
                                                .replace("&laquo;", "←")
                                                .replace("&raquo;", "→")
                                        }
                                    </Button>
                                </Link>
                            ) : (
                                <span key={index} className="px-2 text-gray-400">
                                    {link.label.replace(/&laquo;|&raquo;/g, '')}
                                </span>
                            )
                        ))}
                    </div>
                )}

                <div className="text-center text-sm text-gray-500 mt-4">
                    顯示 {paginatedProductsData.meta.from} 至 {paginatedProductsData.meta.to} 筆，共 {paginatedProductsData.meta.total} 筆商品
                </div>
            </main>
        </AppContent>
    );
}

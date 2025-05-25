import ProductCard from '@/components/product-card';
import { ProductIndex } from '@/types/resource';
import React from 'react';

interface HotProductsSectionProps {
    productIndexes: ProductIndex[];
}

export const HotProductsSection: React.FC<HotProductsSectionProps> = ({ productIndexes }) => {
    return (
        <section className="mx-auto w-full max-w-6xl px-4 py-8">
            <h2 className="mb-8 text-center text-2xl font-bold">熱門商品</h2>
            <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                {productIndexes.map((productIndex) => (
                    <ProductCard
                        key={productIndex.id}
                        figure={productIndex.figure}
                        alt={productIndex.name}
                        name={productIndex.name}
                        summary={productIndex.summary}
                        price={productIndex.price}
                        id={productIndex.id}
                    />
                ))}
            </div>
        </section>
    );
};

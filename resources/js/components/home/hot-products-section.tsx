import React from 'react';
import ProductCard from '@/components/product-card';
import { Data, ProductIndex } from '@/types/resource';

interface HotProductsSectionProps {
    hotProductsData: Data<ProductIndex[]>;
}

export const HotProductsSection: React.FC<HotProductsSectionProps> = ({ hotProductsData }) => {
    return (
        <section className="mx-auto w-full max-w-6xl px-4 py-8">
            <h2 className="mb-8 text-center text-2xl font-bold">熱門商品</h2>
            <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
                {hotProductsData.data.map((productIndex) => (
                    <ProductCard
                        key={productIndex.id}
                        image={productIndex.image}
                        alt={productIndex.name}
                        name={productIndex.name}
                        description={productIndex.description}
                        price={productIndex.price}
                        id={productIndex.id}
                    />
                ))}
            </div>
        </section>
    );
};

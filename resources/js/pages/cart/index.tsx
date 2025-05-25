import { Data, ProductIndex } from '@/types/resource';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { useCallback } from 'react';

interface ProductWithQuantity {
    data: ProductIndex;
    quantity: number;
}

interface Props {
    carts: ProductWithQuantity[];
}

export default function CartPage({ carts }: Props) {
    console.log(carts);

    return (
        <div>
            <h1>Cart</h1>
            <ul>
                {carts.map(({data: product, quantity }) => (
                    <li key={product.slug}>
                        {product.name} (NT${product.price}) x {quantity}
                        <CartQuantity product={product.slug} quantity={quantity} />
                    </li>
                ))}
            </ul>
        </div>
    )
}

function CartQuantity({ product, quantity }: { product: string; quantity: number }) {
    const increment = useCallback(() => {
        router.post(route('carts.increment', { product }), { quantity: 1 })
    }, [product]);

    const decrement = useCallback(() => {
        router.post(route('carts.increment', { product }), { quantity: -1 })
    }, [product]);

    return (
        <div>
            <Button onClick={decrement}>-</Button>
            {quantity}
            <Button onClick={increment}>+</Button>
        </div>
    );
}

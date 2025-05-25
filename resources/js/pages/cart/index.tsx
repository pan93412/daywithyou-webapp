import { ProductIndex } from '@/types/resource';
import { Button } from '@/components/ui/button';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { Link, router } from '@inertiajs/react';
import { Trash2, Minus, Plus, ShoppingBag } from 'lucide-react';
import { useCallback } from 'react';
import { toast } from 'sonner';

interface CartItem {
    data: ProductIndex;
    quantity: number;
}

interface Props {
    carts: CartItem[];
}

export default function CartPage({ carts }: Props) {
    const totalPrice = carts.reduce((sum, { data: product, quantity }) => sum + product.price * quantity, 0);

    return (
        <AppMainLayout title="購物車">
            <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                <div className="mb-6">
                    <h1 className="text-2xl font-bold">購物車</h1>
                    <p className="text-zinc-500">{carts.length} 件商品</p>
                </div>

                {carts.length === 0 ? (
                    <EmptyCart />
                ) : (
                    <div className="flex flex-col gap-6 lg:flex-row">
                        <div className="flex-1">
                            <div className="rounded-xl bg-white p-6 shadow-md">
                                {carts.map(({ data: product, quantity }) => (
                                    <CartItem
                                        key={product.slug}
                                        product={product}
                                        quantity={quantity}
                                    />
                                ))}
                            </div>
                        </div>

                        <div className="lg:w-80">
                            <OrderSummary totalPrice={totalPrice} itemCount={carts.length} />
                        </div>
                    </div>
                )}
            </main>
        </AppMainLayout>
    );
}

function CartItem({ product, quantity }: { product: ProductIndex; quantity: number }) {
    return (
        <div className="border-b border-zinc-100 py-4 last:border-0">
            <div className="flex gap-4">
                <div className="h-24 w-24 flex-shrink-0">
                    <Link href={route('products.show', { slug: product.slug })}>
                        <img
                            src={product.figure}
                            alt={product.name}
                            className="h-full w-full rounded-md border object-contain hover:scale-105 transition"
                        />
                    </Link>
                </div>
                <div className="flex flex-1 flex-col justify-between">
                    <div>
                        <h3 className="font-medium">{product.name}</h3>
                        <p className="text-sm text-zinc-500">{product.summary}</p>
                    </div>
                    <div className="mt-2 flex items-center justify-between">
                        <div className="text-primary font-semibold">NT${product.price}</div>
                        <div className="flex items-center gap-4">
                            <CartQuantity product={product.slug} quantity={quantity} />
                            <CartRemove product={product.slug} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

function CartQuantity({ product, quantity }: { product: string; quantity: number }) {
    const increment = useCallback(() => {
        router.post(
            route('carts.increment', { product }),
            { quantity: 1 },
            { preserveScroll: true }
        );
    }, [product]);

    const decrement = useCallback(() => {
        if (quantity > 1) {
            router.post(
                route('carts.increment', { product }),
                { quantity: -1 },
                { preserveScroll: true }
            );
        }
    }, [product, quantity]);

    return (
        <div className="flex items-center gap-2">
            <Button
                onClick={decrement}
                variant="outline"
                size="icon"
                className="h-8 w-8"
                disabled={quantity <= 1}
            >
                <Minus className="h-4 w-4" />
            </Button>
            <span className="w-8 text-center">{quantity}</span>
            <Button
                onClick={increment}
                variant="outline"
                size="icon"
                className="h-8 w-8"
            >
                <Plus className="h-4 w-4" />
            </Button>
        </div>
    );
}

function CartRemove({ product }: { product: string }) {
    const remove = useCallback(() => {
        router.delete(
            route('carts.remove', { product }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('商品已從購物車移除');
                }
            }
        );
    }, [product]);

    return (
        <Button
            onClick={remove}
            variant="ghost"
            size="icon"
            className="text-red-500 hover:bg-red-50 hover:text-red-600"
        >
            <Trash2 className="h-4 w-4" />
        </Button>
    );
}

function OrderSummary({ totalPrice, itemCount }: { totalPrice: number; itemCount: number }) {
    return (
        <div className="rounded-xl bg-white p-6 shadow-md">
            <h2 className="mb-4 text-lg font-semibold">訂單摘要</h2>

            <div className="space-y-2">
                <div className="flex justify-between">
                    <span>小計 ({itemCount} 件商品)</span>
                    <span>NT${totalPrice}</span>
                </div>
                <div className="flex justify-between">
                    <span>運費</span>
                    <span>免費</span>
                </div>
                <div className="border-t border-zinc-100 pt-2">
                    <div className="flex justify-between font-semibold">
                        <span>總計</span>
                        <span className="text-primary">NT${totalPrice}</span>
                    </div>
                </div>
            </div>

            <Button className="mt-6 w-full">前往結帳</Button>
        </div>
    );
}

function EmptyCart() {
    return (
        <div className="flex flex-col items-center justify-center rounded-xl bg-white p-12 shadow-md">
            <ShoppingBag className="mb-4 h-16 w-16 text-zinc-300" />
            <h2 className="mb-2 text-xl font-semibold">您的購物車是空的</h2>
            <p className="mb-6 text-center text-zinc-500">看起來您尚未將任何商品加入購物車</p>
            <Button onClick={() => router.visit(route('products.index'))}>繼續購物</Button>
        </div>
    );
}

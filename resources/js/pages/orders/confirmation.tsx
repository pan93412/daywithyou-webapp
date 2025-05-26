import { Button } from '@/components/ui/button';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { Data, OrderConfirmation } from '@/types/resource';
import { router } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';

interface Props {
    reply: Data<OrderConfirmation>;
}

export default function PaymentSuccessfulPage({ reply }: Props) {
    return (
        <AppMainLayout title="付款成功">
            <main className="mx-auto w-full max-w-4xl flex-1 px-4 py-8">
                <div className="rounded-xl bg-white p-8 shadow-md">
                    <div className="flex flex-col items-center justify-center text-center">
                        <div className="mb-4 rounded-full bg-emerald-100 p-3">
                            <CheckCircle2 className="h-12 w-12 text-emerald-600" />
                        </div>

                        <h1 className="mb-2 text-2xl font-bold text-emerald-700">付款成功！</h1>
                        <p className="mb-6 text-zinc-500">您的訂單已成功處理，感謝您的購買。</p>

                        <div className="mb-8 w-full max-w-md rounded-lg bg-emerald-50 p-6">
                            <h2 className="mb-4 text-lg font-semibold">訂單資訊</h2>

                            <div className="space-y-3 text-left">
                                <div className="flex justify-between">
                                    <span className="text-zinc-500">訂單編號</span>
                                    <span className="font-medium">#{reply.data.id}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-zinc-500">收貨人</span>
                                    <span className="font-medium">{reply.data.recipient_name}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-zinc-500">付款方式</span>
                                    <span className="font-medium">{formatPaymentMethod(reply.data.payment_method)}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-zinc-500">訂單狀態</span>
                                    <span className="font-medium text-emerald-600">已確認</span>
                                </div>
                            </div>
                        </div>

                        <p className="mb-2 text-zinc-500">我們已發送訂單確認郵件至您的電子信箱。</p>
                        <p className="mb-6 text-zinc-500">如有任何問題，請聯繫我們的客服團隊。</p>

                        <div className="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <Button onClick={() => router.visit(route('products.index'))} className="bg-emerald-600 hover:bg-emerald-700">
                                繼續購物
                            </Button>
                            <Button variant="outline" onClick={() => router.visit(route('dashboard.orders.details', { order: reply.data.id }))}>
                                查看我的訂單
                            </Button>
                        </div>
                    </div>
                </div>
            </main>
        </AppMainLayout>
    );
}

function formatPaymentMethod(paymentMethod: string) {
    switch (paymentMethod) {
        case 'cash':
            return '現金';
        case 'line_pay':
            return 'Line Pay';
        case 'bank_transfer':
            return '銀行轉帳';
        default:
            return paymentMethod;
    }
}

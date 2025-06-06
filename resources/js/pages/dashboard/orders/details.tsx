import { PageMessage } from '@/components/page-message';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import AppLayout from '@/layouts/app-layout';
import { formatDate } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Data, Order } from '@/types/resource';
import { Head, Link, router } from '@inertiajs/react';
import { Calendar, ChevronLeft, CreditCard, FileText, Mail, MapPin, Package, Phone, Truck, User } from 'lucide-react';
import { useCallback, useState } from 'react';
import QRCode from 'react-qr-code';

interface Props {
    reply: Data<Order>;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '訂單管理',
        href: route('dashboard.orders'),
    },
    {
        title: '訂單詳情',
        href: '#',
    },
];

export default function OrderDetailsPage({ reply }: Props) {
    console.log(reply);

    const order = reply.data;
    const items = reply.data.order_items;
    const detailUrl = route('dashboard.orders.details', { order: order.id });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`訂單 #${order.id} 詳情`} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="mb-2 flex items-center justify-between">
                    <Link href={route('dashboard.orders')}>
                        <Button variant="ghost" size="sm" className="flex items-center gap-1">
                            <ChevronLeft className="h-4 w-4" />
                            返回訂單列表
                        </Button>
                    </Link>
                    <CancelButton orderId={order.id} />
                </div>

                <PageMessage className="mb-4" />

                <div className="grid gap-4 md:grid-cols-3">
                    {/* Order Summary Card */}
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm md:col-span-2 dark:bg-neutral-900">
                        <div className="mb-6 flex items-center justify-between">
                            <h1 className="text-2xl font-bold">訂單 #{order.id}</h1>
                            <div className="rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                {getPaymentMethodText(order.payment_method)}
                            </div>
                        </div>

                        <div className="mb-6">
                            <h2 className="mb-4 text-lg font-semibold">訂單項目</h2>
                            <div className="overflow-x-auto">
                                <table className="w-full border-collapse">
                                    <thead>
                                        <tr className="border-b border-neutral-200 dark:border-neutral-700">
                                            <th className="px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-300">
                                                商品
                                            </th>
                                            <th className="px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-300">
                                                數量
                                            </th>
                                            <th className="px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-300">
                                                單價
                                            </th>
                                            <th className="px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-neutral-600 dark:text-neutral-300">
                                                小計
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {items.map((item) => {
                                            const product = item.product;
                                            return (
                                                <tr
                                                    key={item.id}
                                                    className="border-b border-neutral-200 hover:bg-neutral-50 dark:border-neutral-700 dark:hover:bg-neutral-800"
                                                >
                                                    <td className="px-4 py-4 text-sm">
                                                        <div className="flex items-center">
                                                            {product ? (
                                                                <>
                                                                    <div className="mr-3 h-10 w-10 flex-shrink-0 overflow-hidden rounded-md border border-neutral-200">
                                                                        <img
                                                                            src={product.figure}
                                                                            alt={product.name}
                                                                            className="h-full w-full object-cover object-center"
                                                                        />
                                                                    </div>
                                                                    <div>
                                                                        <div className="font-medium">{product.name}</div>
                                                                        <div className="max-w-[200px] truncate text-xs text-neutral-500">
                                                                            {product.summary}
                                                                        </div>
                                                                    </div>
                                                                </>
                                                            ) : (
                                                                <div className="flex items-center">
                                                                    <Package className="mr-2 h-4 w-4 text-neutral-400" />
                                                                    商品 ID: {item.product_id}
                                                                </div>
                                                            )}
                                                        </div>
                                                    </td>
                                                    <td className="px-4 py-4 text-sm whitespace-nowrap">{item.quantity}</td>
                                                    <td className="px-4 py-4 text-sm whitespace-nowrap">{product ? `NT$ ${product.price}` : '-'}</td>
                                                    <td className="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                        {product ? `NT$ ${(parseFloat(product.price) * item.quantity).toFixed(2)}` : '-'}
                                                    </td>
                                                </tr>
                                            );
                                        })}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Order Notes */}
                        {order.note && (
                            <div className="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-800">
                                <div className="mb-2 flex items-center">
                                    <FileText className="mr-2 h-4 w-4 text-neutral-500" />
                                    <h3 className="font-medium">訂單備註</h3>
                                </div>
                                <p className="text-sm text-neutral-600 dark:text-neutral-300">{order.note}</p>
                            </div>
                        )}

                        {/* Order Timeline */}
                        <div>
                            <h2 className="mb-4 text-lg font-semibold">訂單時間線</h2>
                            <div className="space-y-4">
                                <div className="flex">
                                    <div className="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-200">
                                        <Calendar className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p className="font-medium">訂單建立</p>
                                        <p className="text-sm text-neutral-500">{formatDate(new Date(order.created_at))}</p>
                                    </div>
                                </div>
                                <div className="flex">
                                    <div className="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                                        <CreditCard className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p className="font-medium">付款方式</p>
                                        <p className="text-sm text-neutral-500">{getPaymentMethodText(order.payment_method)}</p>
                                    </div>
                                </div>
                                <div className="flex">
                                    <div className="mr-4 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                                        <Truck className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p className="font-medium">出貨狀態</p>
                                        <p className="text-sm text-neutral-500">處理中</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Sidebar: 收件人資訊 & QR Code */}
                    <div className="flex flex-col gap-4">
                        {/* QR Code Card */}
                        <div className="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-neutral-900">
                            <h2 className="mb-2 text-lg font-semibold text-neutral-900 dark:text-neutral-100">現場驗證 QR Code</h2>
                            <div className="mb-4 text-sm text-neutral-600 dark:text-neutral-300">
                                讓工作人員可以在場檢查您購買的項目，並給予通行。
                            </div>
                            <div className="flex flex-col items-center justify-center">
                                <QRCode
                                    value={detailUrl}
                                    size={144}
                                    bgColor="white"
                                    fgColor="#059669"
                                    style={{ background: 'white', borderRadius: 8, padding: 8 }}
                                />
                            </div>
                        </div>

                        {/* Recipient Info Card */}
                        <div className="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border bg-white p-6 shadow-sm dark:bg-neutral-900">
                            <h2 className="mb-4 text-lg font-semibold">收件人資訊</h2>
                            <div className="space-y-4">
                                <div className="flex">
                                    <User className="mr-3 h-5 w-5 text-neutral-400" />
                                    <div>
                                        <p className="text-sm font-medium">姓名</p>
                                        <p className="text-sm text-neutral-600 dark:text-neutral-300">{order.recipient_name}</p>
                                    </div>
                                </div>
                                <div className="flex">
                                    <Mail className="mr-3 h-5 w-5 text-neutral-400" />
                                    <div>
                                        <p className="text-sm font-medium">電子郵件</p>
                                        <p className="text-sm text-neutral-600 dark:text-neutral-300">{order.recipient_email}</p>
                                    </div>
                                </div>
                                <div className="flex">
                                    <Phone className="mr-3 h-5 w-5 text-neutral-400" />
                                    <div>
                                        <p className="text-sm font-medium">電話</p>
                                        <p className="text-sm text-neutral-600 dark:text-neutral-300">{order.recipient_phone}</p>
                                    </div>
                                </div>
                                <div className="flex">
                                    <MapPin className="mr-3 h-5 w-5 text-neutral-400" />
                                    <div>
                                        <p className="text-sm font-medium">地址</p>
                                        <p className="text-sm text-neutral-600 dark:text-neutral-300">
                                            {order.recipient_zip_code} {order.recipient_city}
                                            <br />
                                            {order.recipient_address}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

function CancelButton({ orderId }: { orderId: number }) {
    const [open, setOpen] = useState(false);
    const [processing, setProcessing] = useState(false);

    const handleCancelOrder = useCallback(() => {
        setProcessing(true);

        router.delete(route('orders.cancel', { order: orderId }), {
            onSuccess: () => {
                setOpen(false);
                setProcessing(false);
            },
        });
    }, [orderId]);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="destructive" size="sm">
                    取消訂單
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogTitle>確定要取消此訂單嗎？</DialogTitle>
                <DialogDescription>一旦您取消訂單，訂單將被永久刪除。這個動作無法撤銷。</DialogDescription>

                <DialogFooter className="gap-2">
                    <DialogClose asChild>
                        <Button variant="secondary">取消</Button>
                    </DialogClose>

                    <Button variant="destructive" disabled={processing} onClick={handleCancelOrder}>
                        確定取消訂單
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}

function getPaymentMethodText(method: string): string {
    switch (method) {
        case 'cash':
            return '貨到付款';
        case 'line_pay':
            return 'LINE Pay';
        case 'bank_transfer':
            return '銀行轉帳';
        default:
            return method;
    }
}

import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { MessageSquare } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '總覽',
        href: '#',
    },
];

interface Props {
    commentCount: number;
}

export default function Dashboard({ commentCount }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="總覽" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video flex-col items-center justify-center gap-2 overflow-hidden rounded-xl border bg-white p-4 text-center shadow-sm transition-shadow hover:shadow-md dark:bg-neutral-900">
                        <MessageSquare className="pointer-events-none absolute top-1/2 left-1/2 h-40 w-40 -translate-x-1/2 -translate-y-1/2 text-blue-100 opacity-40 dark:text-blue-900" />
                        <span className="relative z-10 text-4xl font-bold">{commentCount}</span>
                        <span className="relative z-10 text-sm text-neutral-600 dark:text-neutral-400">總留言數</span>
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}

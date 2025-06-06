import AppLogoIcon from '@/components/app-logo-icon';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet';
import type { SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { Badge, Menu, Search, ShoppingCart, User } from 'lucide-react';
import { useRef } from 'react';

const navbarItems = [
    {
        name: '商品列表',
        href: route('products.index'),
    },
    {
        name: '新品上市',
        href: '/new-arrivals',
    },
    {
        name: '最新消息',
        href: route('news.index'),
    },
    {
        name: '關於我們',
        href: '/about',
    },
];

export interface AppHeaderProps {
    title: string;
}

export function AppHeader({ title }: AppHeaderProps) {
    const {
        props: { auth, cartCount },
    } = usePage<SharedData>();
    const formRef = useRef<HTMLFormElement | null>(null);

    return (
        <header className="sticky top-0 z-50 w-full border-b bg-white/80 backdrop-blur-md">
            <Head title={title}>
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>

            <div className="flex h-16 w-full items-center justify-around">
                <div className="flex items-center gap-2">
                    <Sheet>
                        <SheetTrigger asChild>
                            <Button variant="ghost" size="icon" className="md:hidden">
                                <Menu className="h-5 w-5" />
                                <span className="sr-only">切換選單</span>
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" className="w-[300px] sm:w-[400px]">
                            <nav className="flex flex-col gap-4 pt-10 pl-4">
                                {navbarItems.map((item) => (
                                    <Link key={item.name} href={item.href} className="text-lg font-medium transition-colors hover:text-emerald-600">
                                        {item.name}
                                    </Link>
                                ))}
                            </nav>
                        </SheetContent>
                    </Sheet>
                    <Link href={route('home')} className="flex items-center gap-2">
                        <div className="relative h-10 w-10 overflow-hidden rounded-full bg-emerald-500">
                            <AppLogoIcon />
                        </div>
                        <span className="text-xl font-bold tracking-tight">潑墨日子</span>
                    </Link>
                </div>

                <div className="mx-4 hidden max-w-sm flex-1 lg:flex">
                    <div className="relative w-full">
                        <form ref={formRef} action="/products" method="get">
                            <Search className="absolute top-2.5 left-2.5 h-4 w-4 text-zinc-500" />
                            <Input
                                type="search"
                                name="q"
                                placeholder="搜尋商品……"
                                className="w-full bg-zinc-100 pl-8 focus-visible:ring-emerald-500"
                            />
                        </form>
                    </div>
                </div>

                <nav className="hidden md:flex md:items-center md:gap-6">
                    {navbarItems.map((item) => (
                        <Link key={item.name} href={item.href} className="text-sm font-medium transition-colors hover:text-emerald-600">
                            {item.name}
                        </Link>
                    ))}
                </nav>

                <div className="flex items-center gap-4">
                    <Button variant="ghost" size="icon" className="relative lg:hidden" aria-label="Search">
                        <Search className="h-5 w-5" />
                    </Button>
                    <Link href={route('carts.index')}>
                        <Button variant="ghost" size="icon" className="relative" aria-label="Shopping cart">
                            <ShoppingCart className="h-5 w-5" />
                            {cartCount > 0 && (
                                <Badge className="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-emerald-500 p-0 text-xs text-white">
                                    {cartCount}
                                </Badge>
                            )}
                        </Button>
                    </Link>
                    <Link href={route('dashboard')}>
                        {auth.user ? (
                            <Button variant="ghost" size="icon" aria-label="User account">
                                <User className="h-5 w-5" />
                            </Button>
                        ) : (
                            <Button variant="outline" size="default" aria-label="Login">
                                登入
                            </Button>
                        )}
                    </Link>
                </div>
            </div>
        </header>
    );
}

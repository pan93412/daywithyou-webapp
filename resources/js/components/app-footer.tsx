import { SiFacebook, SiInstagram } from '@icons-pack/react-simple-icons';
import { Link } from '@inertiajs/react';

export function AppFooter() {
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-zinc-800 py-12 text-zinc-300">
            <div className="mx-auto max-w-6xl px-4">
                <div className="mb-8 grid gap-8 md:grid-cols-4">
                    <div>
                        <h3 className="mb-4 text-lg font-bold text-white">關於我們</h3>
                        <ul className="space-y-2">
                            <li>
                                <Link href="/about" className="hover:text-emerald-400">
                                    品牌故事
                                </Link>
                            </li>
                            <li>
                                <Link href="/contact" className="hover:text-emerald-400">
                                    聯絡我們
                                </Link>
                            </li>
                            <li>
                                <Link href="/careers" className="hover:text-emerald-400">
                                    加入我們
                                </Link>
                            </li>
                            <li>
                                <Link href="/faq" className="hover:text-emerald-400">
                                    常見問題
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 className="mb-4 text-lg font-bold text-white">客戶服務</h3>
                        <ul className="space-y-2">
                            <li>
                                <Link href="/shipping" className="hover:text-emerald-400">
                                    運送資訊
                                </Link>
                            </li>
                            <li>
                                <Link href="/returns" className="hover:text-emerald-400">
                                    退換政策
                                </Link>
                            </li>
                            <li>
                                <Link href="/order-tracking" className="hover:text-emerald-400">
                                    訂單查詢
                                </Link>
                            </li>
                            <li>
                                <Link href="/help" className="hover:text-emerald-400">
                                    幫助中心
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 className="mb-4 text-lg font-bold text-white">商品分類</h3>
                        <ul className="space-y-2">
                            <li>
                                <Link href="/categories/handcraft" className="hover:text-emerald-400">
                                    手工藝品
                                </Link>
                            </li>
                            <li>
                                <Link href="/categories/home-decor" className="hover:text-emerald-400">
                                    家居裝飾
                                </Link>
                            </li>
                            <li>
                                <Link href="/categories/stationery" className="hover:text-emerald-400">
                                    文具用品
                                </Link>
                            </li>
                            <li>
                                <Link href="/categories/gift-wrap" className="hover:text-emerald-400">
                                    禮品包裝
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 className="mb-4 text-lg font-bold text-white">聯絡我們</h3>
                        <address className="not-italic">
                            <p className="mb-2">高雄市左營區文府路175巷3號</p>
                            <p className="mb-4">
                                預約連結：<a href="https://lin.ee/Ku47AjF">lin.ee/Ku47AjF</a>
                            </p>
                        </address>
                        <div className="flex space-x-4">
                            <a
                                href="https://www.instagram.com/daywithyou2023"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="text-zinc-300 hover:text-emerald-400"
                                aria-label="Instagram"
                            >
                                <SiInstagram />
                            </a>
                            <a
                                href="https://www.facebook.com/p/%E6%97%A5%E5%AD%90%E6%9C%89%E4%BD%A0%E6%BD%91%E5%A2%A8%E8%A8%88%E7%95%AB-61553323156245/?locale=zh_TW"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="text-zinc-300 hover:text-emerald-400"
                                aria-label="Facebook"
                            >
                                <SiFacebook />
                            </a>
                        </div>
                    </div>
                </div>

                <div className="border-t border-zinc-700 pt-8">
                    <div className="flex flex-col justify-between space-y-4 md:flex-row md:space-y-0">
                        <div>
                            <p>&copy; {currentYear} 潑墨日子</p>
                        </div>
                        <div>
                            <ul className="flex space-x-6">
                                <li>
                                    <Link href="/privacy" className="hover:text-emerald-400">
                                        隱私權政策
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/terms" className="hover:text-emerald-400">
                                        使用條款
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/sitemap" className="hover:text-emerald-400">
                                        網站地圖
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    );
}

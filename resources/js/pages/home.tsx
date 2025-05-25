import { AppHeader } from '@/components/app-header';
import { AppContent } from '@/components/app-content';
import { Data, ProductIndex } from '@/types/resource';
import { AppFooter } from '@/components/app-footer';

// Import the new components
import { HotProductsSection } from '@/components/home/hot-products-section';
import { NewsBanner } from '@/components/home/news-banner';
import { Testimonials } from '@/components/home/testimonials';
import { Newsletter } from '@/components/home/newsletter';
import { Deferred } from '@inertiajs/react';

interface Props {
    hotProductsData: Data<ProductIndex[]>;
    newsItems?: {
        title: string;
        slug: string;
    }[];
    testimonials: {
        id: number;
        name: string;
        avatar: string;
        text: string;
        rating: number;
    }[];
}

export default function Home({
    hotProductsData,
    newsItems,
    testimonials
}: Props) {
    return (
        <>
            <AppContent>
                <AppHeader title="首頁" />

                {/* News Banner */}
                <Deferred fallback={<></>} data="newsItems">
                    <NewsBanner news={newsItems ?? []} />
                </Deferred>

                <main className="flex-1">
                    {/* Hot Products Section */}
                    <HotProductsSection hotProductsData={hotProductsData} />

                    {/* Testimonials */}
                    <Testimonials testimonials={testimonials} />

                    {/* Newsletter Subscription */}
                    <Newsletter />
                </main>
            </AppContent>

            {/* Footer */}
            <AppFooter />
        </>
    );
}

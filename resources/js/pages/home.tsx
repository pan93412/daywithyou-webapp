import { HotProductsSection } from '@/components/home/hot-products-section';
import { NewsBanner } from '@/components/home/news-banner';
import { Newsletter } from '@/components/home/newsletter';
import { Testimonials } from '@/components/home/testimonials';
import AppMainLayout from '@/layouts/app/app-main-layout';
import { Data, ProductIndex } from '@/types/resource';
import { Deferred } from '@inertiajs/react';

interface Props {
    hotProductsReply: Data<ProductIndex[]>;
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

export default function Home({ hotProductsReply, newsItems, testimonials }: Props) {
    return (
        <AppMainLayout title="首頁">
            {/* News Banner */}
            <Deferred fallback={<NewsBanner news={[]} />} data="newsItems">
                <NewsBanner news={newsItems ?? []} />
            </Deferred>

            <main className="flex-1">
                {/* Hot Products Section */}
                <HotProductsSection productIndexes={hotProductsReply.data} />

                {/* Testimonials */}
                <Testimonials testimonials={testimonials} />

                {/* Newsletter Subscription */}
                <Newsletter />
            </main>
        </AppMainLayout>
    );
}

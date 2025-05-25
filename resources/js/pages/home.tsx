import { AppHeader } from '@/components/app-header';
import { AppContent } from '@/components/app-content';
import { Data, ProductIndex } from '@/types/resource';

// Import the new components
import { HotProductsSection } from '@/components/home/hot-products-section';
import { NewsBanner } from '@/components/home/news-banner';
import { FeaturedCategories } from '@/components/home/featured-categories';
import { Testimonials } from '@/components/home/testimonials';
import { Newsletter } from '@/components/home/newsletter';

interface Props {
    hotProductsData: Data<ProductIndex[]>;
    newsItems: {
        id: number;
        title: string;
        url: string;
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
        <AppContent>
            <AppHeader title="首頁" />

            {/* News Banner */}
            <NewsBanner news={newsItems} />

            <main className="flex-1">
                {/* Hot Products Section */}
                <HotProductsSection hotProductsData={hotProductsData} />

                {/* Testimonials */}
                <Testimonials testimonials={testimonials} />

                {/* Newsletter Subscription */}
                <Newsletter />
            </main>
        </AppContent>
    );
}

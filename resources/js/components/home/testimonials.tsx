import React from 'react';

interface Testimonial {
    id: number;
    name: string;
    avatar: string;
    text: string;
    rating: number;
}

interface TestimonialsProps {
    testimonials: Testimonial[];
}

export const Testimonials: React.FC<TestimonialsProps> = ({ testimonials }) => {
    return (
        <section className="bg-zinc-50 py-12">
            <div className="mx-auto max-w-6xl px-4">
                <h2 className="mb-8 text-center text-2xl font-bold">商品評價</h2>
                <div className="grid gap-6 md:grid-cols-3">
                    {testimonials.map((testimonial) => (
                        <div key={testimonial.id} className="rounded-lg bg-white p-6 shadow-md">
                            <div className="mb-4 flex items-center">
                                <img
                                    src={testimonial.avatar}
                                    alt={testimonial.name}
                                    className="mr-3 h-12 w-12 rounded-full object-cover"
                                />
                                <div>
                                    <h3 className="font-semibold">{testimonial.name}</h3>
                                    <div className="flex text-amber-400">
                                        {[...Array(5)].map((_, i) => (
                                            <svg
                                                key={i}
                                                xmlns="http://www.w3.org/2000/svg"
                                                className={`h-4 w-4 ${i < testimonial.rating ? 'fill-current' : 'text-zinc-200'}`}
                                                viewBox="0 0 20 20"
                                            >
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        ))}
                                    </div>
                                </div>
                            </div>
                            <p className="text-zinc-600">{testimonial.text}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
};

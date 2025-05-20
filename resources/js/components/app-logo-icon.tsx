export default function AppLogoIcon({ className = 'object-cover', ...props }: Omit<React.ComponentPropsWithoutRef<'img'>, 'src' | 'alt'>) {
    return <img className={className} src="/logo.jpg" alt="潑墨日子" {...props} />;
}

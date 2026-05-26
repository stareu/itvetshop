import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
	user: User,
	products?: Product[],
	cart?: {
		totalQuantity: number,
		products: number[]
	},
	orders: Order[],
	cartProducts?: Product[],
	cartTotalSum?: string,
	order?: Order,
    [key: string]: unknown;
}

export interface Order {
	id: number,
	product_name: string,
	payment_data: string,
	product_price: number,
	quantity: number,
	image_url: string,
	status: string,
	created_at: string,
	total_amount: string,
	order_items: OrderItem[]
}

interface OrderItem {
	id: number,
	product_id: number,
	product_name: string,
	product_price: number,
	quantity: number,
	assets?: string[]
}

export interface Product {
	id: number,
	name: string,
	price: number,
	price_text: string,
	quantity: number,
	image: string,
	description: string,
	is_sold: boolean
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
	is_admin: boolean,
	can_logout: boolean,
    [key: string]: unknown; // This allows for additional properties...
}
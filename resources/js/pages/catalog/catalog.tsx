import { usePage } from '@inertiajs/react'
import { Empty } from 'antd'
import { SharedData } from '@/types'
import ProductCard from './product-card'

export default function Catalog() {
	const {props} = usePage<SharedData>()

	return (
		<div className="flex flex-grow w-full flex-col gap-4 mt-6">
			<h1 className="text-2xl font-bold">Каталог</h1>

			<div className="grid grid-cols-2 gap-4 lg:grid-cols-[repeat(auto-fit,minmax(300px,min-content))]">
				{!props.products?.length
					? <Empty description="Товаров нет." />
					: props.products.map(product => (
						<ProductCard key={product.name} product={product}/>
					))
				}
			</div>
		</div>
	)
}
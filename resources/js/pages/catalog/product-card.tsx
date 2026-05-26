import { Button } from 'antd'
import { Product } from '@/types'
import { router } from '@inertiajs/react'
import { usePage } from '@inertiajs/react'
import { SharedData } from '@/types'
import { useState } from 'react'
import axios from 'axios'
import productImage from '@/assets/product.webp'

export default function ProductCard({ product }: { product: Product }) {
	const {props} = usePage<SharedData>()
	const [loading, setLoading] = useState<boolean>()

	return (
		<div className="border rounded-lg p-2 lg:w-[300px] flex gap-4 flex-col justify-between">
			<div className="flex-grow rounded overflow-hidden">
				<img
					src={product.image || productImage}
					style={{
						objectFit: 'cover',
						objectPosition: 'center',
						height: '100%'
					}}
					className="w-full"
				/>
			</div>

			<div>
				<div className="text-lg">
					{product.name}
				</div>

				<div className="text-xl flex-grow font-bold">
					{product.price}
				</div>
			</div>

			{product.is_sold
				? (
					<Button
						color="green"
						variant="solid"
						disabled
						style={{ width: '100%' }}
					>
						Распродан
					</Button>
				)
				: props.cart?.products.includes(product.id)
					? (
						<Button
							color="green"
							variant="solid"
							style={{ width: '100%' }}
							onClick={() => (
								router.visit('cart')
							)}
						>
							Оформить
						</Button>
					)
					: (
						<Button
							color="primary"
							variant="solid"
							style={{ width: '100%' }}
							loading={loading}
							onClick={() => {
								setLoading(true)

								axios.post('cart', {
									product_id: product.id,
									quantity: 1
								})
								.finally(() => {
									router.reload({
										only: ['cart'],
										onFinish: () => setLoading(false)
									})
								})
								// router.visit('cart', {
								// 	method: 'post',
								// 	data: {
								// 		product_id: product.id,
								// 		quantity: 1
								// 	},
								// 	only: ['cart'],
								// 	preserveScroll: true,
								// 	preserveState: true,
								// 	onBefore: () => setLoading(true),
								// 	onFinish: () => setLoading(false),
								// 	onError: data => message.error(data.product_id, 3)
								// })
							}}
						>
							Купить
						</Button>
					)
			}
		</div>
	)
}
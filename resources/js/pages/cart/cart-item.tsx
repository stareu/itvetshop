import { InputNumber, Alert } from "antd"
import { Product } from "@/types"
import { router } from "@inertiajs/react"
import productImage from '@/assets/product.webp'

export default function CartItem({ product, error }: { product: Product, error: string }) {
	return (
		<div className="flex flex-wrap gap-4 justify-between items-end">
			<div className="flex gap-4">
				<div className="w-[80px] h-[80px] rounded-lg overflow-hidden">
					<img
						className="h-[80px] object-cover object-center"
						src={product.image || productImage}
					/>
				</div>

				<div className="flex flex-col justify-between py-[3px]">
					<div className="text-lg font-base">{product.name}</div>
					<div className="text-xl font-bold">{product.price_text}</div>
				</div>
			</div>
			
			<div>
				<InputNumber
					mode="spinner"
					min={0}
					max={30}
					defaultValue={product.quantity}
					onChange={value => {
						router.visit('cart', {
							method: 'post',
							data: {
								product_id: product.id,
								quantity: value !== null ? value : product.quantity
							},
							preserveScroll: true,
							preserveState: true,
							only: [
								'cartProducts',
								'cartTotalSum',
								'cart'
							]
						})
					}}
					style={{ width: 140 }}
				/>
			</div>

			{error && (
				<Alert
					style={{ flexBasis: '100%', padding: 12 }}
					type="error"
					description={error}
				/>
			)}
		</div>
	)
}
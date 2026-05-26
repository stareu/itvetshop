import { Order } from "@/types"
import { Collapse, Button } from "antd"
import productImage from '@/assets/product.webp'

export default function OrderCard({ order }: { order: Order }) {
	return (
		<div className="flex flex-col gap-5 p-4 rounded-lg bg-[#fbfbfb] w-full">
			<div>
				<div className="text-xl font-bold flex justify-between">
					<div>Заказ №{order.id}</div>
					<div>{order.total_amount}</div>
				</div>

				<div className="text-md flex justify-between">
					<div>{order.created_at}</div>
					<div>{order.status}</div>
				</div>
			</div>

			<div className="flex gap-4 flex-col">
			{order.order_items.map(orderItem => {
				return (
					<div className="flex flex-col gap-3">
						<div className="flex gap-3">
							<div
								style={{ width: 73, height: 73 }}
								className="rounded overflow-hidden"
							>
								<img
									src={orderItem.image_url || productImage }
									style={{
										objectFit: 'cover',
										objectPosition: 'center',
										height: '100%'
									}}
									alt={orderItem.product_name}
								/>
							</div>

							<div>
								<div className="text-lg font-bold">{orderItem.product_name}</div>
								<div>Стоимость: {orderItem.product_price}</div>
								<div>Количество: {orderItem.quantity}</div>
							</div>
						</div>

						{orderItem.assets && (
							<Collapse
								bordered={false}
								items={[{
									key: 'assets',
									label: 'Данные',
									children: (
										<div className="whitespace-pre">{orderItem.assets.join('\n')}</div>
									)
								}]}
							/>
						)}
					</div>
				)
			})}
			</div>

			<div>
				{order.payment_data && (
					<Button
						type="primary"
						href={order.payment_data}
						target="_blank"
						size="large"
					>
						Оплатить
					</Button>
				)}
			</div>
		</div>
	)
}
import { Button, Empty, Radio, Alert, Input, Form as AntForm } from "antd"
import { usePage, Form, Head } from "@inertiajs/react"
import { SharedData } from "@/types"
import CartItem from "./cart-item"
import tbank from '@/assets/tbank.webp'
// import '@/utils/cdek-widget.js'
// import { useEffect } from "react"

export default function Cart() {
	const {props} = usePage<SharedData>()

	// useEffect(() => {
	// 	new window.CDEKWidget({
	// 		from: 'Новосибирск',
	// 		root: 'cdek-map',
	// 		apiKey: '37b97bc1-9fb3-4f16-82a1-7f01de056205',
	// 		servicePath: 'https://shop.itvet.ru/public/service.php',
	// 		defaultLocation: 'Новосибирск',
	// 		onCalculate: console.log,
	// 		goods: [{
	// 			width: 100, 
	// 			height: 100, 
	// 			length: 100, 
	// 			weight: 5000
	// 		}],
	// 		tariffs: {
	// 			pickup: [509,510,366,368],
	// 			office: [234, 136, 138],
	// 			door: [233, 137, 139],
	// 		}
	// 	})
	// }, [])

	return (
		<div className="flex flex-grow w-full flex-col gap-4 mt-6">
			<Head title="Моя корзина"/>

			<div className="flex justify-between border-b pb-4">
				<h1 className="text-xl font-medium">Корзина</h1>

				<div className="text-xl font-medium">Товаров: {props.cartProducts?.length || 0}</div>
			</div>

			{props.cartProducts?.length
			? (
				<Form
					action="checkout"
					method="POST"
					disableWhileProcessing
					className="flex flex-col justify-between flex-grow"
				>
				{({processing, errors, clearErrors}) => {
					return (
						<div className="flex gap-4 flex-col justify-between flex-grow">
							<div className="flex gap-7 flex-col flex-grow">
								{errors.cart_updated_remove && (
									<Alert
										type="warning"
										description={errors.cart_updated_remove}
									/>
								)}

								{props.cartProducts?.map(product => (
									<CartItem
										key={product.id}
										error={errors[`product.${product.id}`]}
										product={product}
									/>
								))}
							</div>

							{!props.user && (
								<div>
									<AntForm.Item
										layout="vertical"
										label="Email"
										validateStatus={errors.email ? 'error' : ''}
										help={errors.email}
									>
										<Input
											name="email"
											size="large"
											type="email"
										/>
									</AntForm.Item>
								</div>
							)}

							{/* <div className="flex flex-col gap-4">
								<div id="cdek-map" style={{width:800,height:600}}></div>
							</div> */}

							<div className="flex flex-col gap-4">
								<div className="text-lg font-bold">Способ оплаты</div>

								<Radio.Group
									name="payment_system"
									defaultValue="tbank"
									onChange={() => clearErrors()}
									options={[
										{
											value: 'tbank',
											label: (
												<img
													src={tbank}
													width={180}
													height={80}
													alt="ТБанк"
												/>
											),
										},
									]}
								/>

								{errors.payment_system && (
									<Alert
										type="error"
										description={errors.payment_system}
									/>
								)}
							</div>

							<div className="flex justify-between items-end">
								<div className="text-lg font-bold">Итого</div>
								<div className="text-2xl font-bold">
									{props.cartTotalSum}
								</div>
							</div>

							<Button
								type="primary"
								size="large"
								htmlType="submit"
								loading={processing}
								onClick={() => clearErrors()}
							>
								Оплатить
							</Button>
						</div>
					)
				}}
				</Form>
			)
			: (
				<Empty description="Корзина пуста." />
			)
			}

		</div>
	)
}
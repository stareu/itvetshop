import { Link } from "@inertiajs/react"
import { CheckCircleOutlined } from "@ant-design/icons"
import { usePage } from "@inertiajs/react"
import { SharedData } from "@/types"
import OrderCard from "../orders/order-card"

export default function PaymentSuccess() {
	const {props} = usePage<SharedData>()

	return (
		<div className="flex text-center items-center flex-grow w-full flex-col gap-4 mt-6">
			<h1 className="text-2xl">Ваш заказ успешно оплачен!</h1>

			<CheckCircleOutlined style={{ fontSize: 33, color: 'green' }}/>

			{props.user && (
				<div>
					<Link href="orders" className="underline text-blue-600 hover:text-blue-800">
						Мои заказы
					</Link>
				</div>
			)}

			<div className="flex w-full md:w-[50%] text-left">
				{props.order && (
					<OrderCard order={props.order} />
				)}
			</div>
		</div>
	)
}
import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/react';
import { Button, Checkbox } from 'antd';

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}

// todo: Кнопку другой цвет + пропадает cart

export default function Login({
    status,
    canResetPassword,
    canRegister,
}: LoginProps) {
    return (
        <div className="flex flex-grow w-full flex-col gap-4 mt-6">
            <Head title="Вход" />

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col gap-6 w-full md:w-[400px] mt-8 mx-auto"
            >
                {({ processing, errors }) => (
                    <>
						<h1 className="text-xl font-bold mb-4">Вход</h1>

                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="email"
                                />
                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Пароль</Label>

                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="ml-auto text-sm"
                                            tabIndex={5}
                                        >
                                            Забыли пароль?
                                        </TextLink>
                                    )}
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    tabIndex={2}
                                    autoComplete="current-password"
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="flex gap-3 items-center space-x-3">
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    tabIndex={3}
                                />

                                <Label htmlFor="remember">Запомнить</Label>
                            </div>

                            <Button
                                htmlType="submit"
								type="primary"
                                className="mt-4 w-full"
                                tabIndex={4}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing && <Spinner />}
                                Войти
                            </Button>
                        </div>

                        {canRegister && (
                            <div className="text-center text-sm text-muted-foreground">
                                Нет аккаунта?{' '}
                                <TextLink href={register()} tabIndex={5}>
                                    Зарегистрироваться
                                </TextLink>
                            </div>
                        )}
                    </>
                )}
            </Form>

            {status && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {status}
                </div>
            )}
        </div>
    );
}

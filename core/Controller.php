<?php

declare(strict_types=1);

namespace Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view(string $view, array $data = [], string $layout = 'app'): void
    {
        View::render($view, $data, $layout);
    }

    protected function redirect(string $url): never
    {
        $this->response->redirect($url);
    }

    protected function back(): never
    {
        $this->response->back();
    }

    protected function json(mixed $data, int $status = 200): never
    {
        $this->response->json($data, $status);
    }

    protected function withErrors(array $errors, string $redirectUrl): never
    {
        Session::flash('errors', $errors);
        Session::flash('old_input', $this->request->all());
        $this->redirect($redirectUrl);
    }

    protected function withSuccess(string $message, string $redirectUrl): never
    {
        Session::flash('success', $message);
        $this->redirect($redirectUrl);
    }

    protected function withError(string $message, string $redirectUrl): never
    {
        Session::flash('error', $message);
        $this->redirect($redirectUrl);
    }

    protected function authorize(): void
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function authorizeAdmin(): void
    {
        if (!Session::isAdmin()) {
            $this->response->forbidden();
        }
    }

    protected function userId(): int
    {
        return Session::userId() ?? 0;
    }
}

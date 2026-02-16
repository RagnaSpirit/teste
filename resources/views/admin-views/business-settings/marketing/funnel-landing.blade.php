@extends('layouts.admin.app')

@section('title', 'Funnel Landing Content')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon"><i class="tio-edit"></i></span>
            <span>Funnel Landing Content (JSON)</span>
        </h1>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="mb-3">
                Customize textos e blocos das páginas de funil em JSON. Se vazio, o sistema usa fallback padrão.
            </p>
            <form method="post" action="{{ route('admin.business-settings.marketing.funnel-landing.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">JSON content</label>
                    <textarea name="funnel_content_v1" class="form-control" rows="22" placeholder='{"download_app":{"title":"..."}}'>{{ old('funnel_content_v1', $json) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn--primary">Salvar conteúdo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

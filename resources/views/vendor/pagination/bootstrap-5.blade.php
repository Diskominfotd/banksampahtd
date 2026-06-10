@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0"
            style="gap: 4px; justify-content: center; list-style: none; display: flex; flex-wrap: wrap; padding: 0;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; border-radius: 8px;
                        background: #f1f5f1; color: #a0b8a0; cursor: not-allowed;
                        font-size: 14px; border: 1px solid #d4e6d4;
                    ">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; border-radius: 8px;
                        background: #fff; color: #2e7d32; cursor: pointer;
                        font-size: 14px; border: 1px solid #a5d6a7;
                        text-decoration: none; transition: all 0.2s;
                    "
                    onmouseover="this.style.background='#e8f5e9'; this.style.borderColor='#2e7d32';"
                    onmouseout="this.style.background='#fff'; this.style.borderColor='#a5d6a7';">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span style="
                            display: flex; align-items: center; justify-content: center;
                            width: 36px; height: 36px; border-radius: 8px;
                            background: #f1f5f1; color: #a0b8a0;
                            font-size: 14px; border: 1px solid #d4e6d4;
                        ">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span style="
                                    display: flex; align-items: center; justify-content: center;
                                    width: 36px; height: 36px; border-radius: 8px;
                                    background: #2e7d32; color: #fff;
                                    font-size: 14px; font-weight: 600;
                                    border: 1px solid #2e7d32;
                                    box-shadow: 0 2px 8px rgba(46,125,50,0.25);
                                ">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="
                                    display: flex; align-items: center; justify-content: center;
                                    width: 36px; height: 36px; border-radius: 8px;
                                    background: #fff; color: #2e7d32;
                                    font-size: 14px; font-weight: 500;
                                    border: 1px solid #a5d6a7;
                                    text-decoration: none; transition: all 0.2s;
                                "
                                onmouseover="this.style.background='#e8f5e9'; this.style.borderColor='#2e7d32'; this.style.color='#1b5e20';"
                                onmouseout="this.style.background='#fff'; this.style.borderColor='#a5d6a7'; this.style.color='#2e7d32';">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; border-radius: 8px;
                        background: #fff; color: #2e7d32;
                        font-size: 14px; border: 1px solid #a5d6a7;
                        text-decoration: none; transition: all 0.2s;
                    "
                    onmouseover="this.style.background='#e8f5e9'; this.style.borderColor='#2e7d32';"
                    onmouseout="this.style.background='#fff'; this.style.borderColor='#a5d6a7';">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li>
                    <span style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; border-radius: 8px;
                        background: #f1f5f1; color: #a0b8a0; cursor: not-allowed;
                        font-size: 14px; border: 1px solid #d4e6d4;
                    ">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif

        </ul>

        {{-- Info --}}
        <p style="text-align: center; margin-top: 10px; font-size: 12px; color: #6b8f6b;">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>
    </nav>
@endif
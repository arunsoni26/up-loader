You are not allowed to see the dashboard. <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">Log out</a>
                    <form id="logout-form" action="{{ route("logout") }}" method="POST" class="d-none">
                        @csrf
                    </form>
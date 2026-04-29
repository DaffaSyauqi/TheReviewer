<template>
    <div
        class="animate-fade-in-left relative mx-auto flex aspect-square max-w-100 items-center justify-center lg:max-w-none"
        style="animation-duration: 1.2s"
    >
        <div
            class="absolute z-0 h-[90%] w-[90%] animate-[spin_30s_linear_infinite] rounded-full border-4 border-dashed border-foreground/30"
        ></div>
        <div
            class="absolute z-0 h-[110%] w-[110%] rounded-full border-2 border-foreground/10"
        ></div>

        <div
            class="absolute top-[5%] right-[5%] z-20 flex h-12 w-12 animate-bounce items-center justify-center border-4 border-foreground bg-background shadow-[6px_6px_0_var(--color-foreground)]"
        >
            <div
                class="h-3 w-3 animate-pulse border-2 border-foreground bg-accent"
            ></div>
        </div>

        <div
            class="absolute right-[-5%] bottom-[10%] z-20 flex h-16 w-16 items-center justify-center rounded-full bg-foreground shadow-[8px_8px_0_var(--color-accent)]"
        >
            <div
                class="border- [3px] h-6 w-6 animate-spin rounded-full border-background border-t-accent"
            ></div>
        </div>

        <div
            class="relative z-10 h-3/4 w-3/4 overflow-hidden rounded-full border-[6px] border-foreground bg-accent shadow-[24px_24px_0_var(--color-foreground)]"
        >
            <div class="grid-lines absolute inset-0 mix-blend-overlay"></div>

            <div
                class="absolute top-1/2 left-1/2 h-[140%] w-[140%] -translate-x-1/2 -translate-y-1/2 rounded-[50%] border-2 border-foreground opacity-50"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 h-[180%] w-[180%] -translate-x-1/2 -translate-y-1/2 rounded-[50%] border-2 border-foreground opacity-50"
            ></div>
            <div
                class="absolute top-1/2 h-1 w-full -translate-y-1/2 bg-foreground opacity-60"
            ></div>
            <div
                class="absolute left-1/2 h-full w-1 -translate-x-1/2 bg-foreground opacity-60"
            ></div>

            <div
                class="absolute top-[15%] left-[10%] h-[30%] w-[40%] bg-foreground"
            >
                <div
                    class="h-full w-full bg-[repeating-linear-gradient(45deg,transparent,transparent_4px,var(--color-background)_4px,var(--color-background)_8px)] opacity-30"
                ></div>
            </div>
            <div
                class="absolute top-[55%] left-[20%] h-[20%] w-[25%] bg-foreground"
            ></div>
            <div
                class="absolute top-[25%] right-[10%] h-[45%] w-[35%] bg-foreground"
                style="clip-path: polygon(0% 10%, 100% 0%, 90% 100%, 10% 90%)"
            ></div>

            <div
                class="absolute top-[25%] left-[30%] flex items-center justify-center"
            >
                <div
                    class="absolute z-10 h-4 w-4 border-2 border-foreground bg-background"
                ></div>
                <div
                    class="absolute h-10 w-10 animate-ping border-2 border-background"
                ></div>
            </div>

            <div
                class="absolute top-[65%] left-[30%] flex items-center justify-center"
            >
                <div
                    class="absolute h-4 w-4 rounded-full border-2 border-foreground bg-background"
                ></div>
            </div>

            <div
                class="absolute top-[45%] right-[25%] flex items-center justify-center"
            >
                <div
                    class="absolute z-10 h-5 w-5 border-[3px] border-background bg-accent"
                ></div>
                <div
                    class="absolute h-12 w-12 animate-ping rounded-full bg-background/30"
                ></div>
            </div>
        </div>

        <div
            class="absolute bottom-4 -left-4 z-30 border border-background/20 bg-foreground p-5 font-mono text-[10px] leading-tight text-background shadow-[8px_8px_0_var(--color-accent)] backdrop-blur-sm md:-left-12"
        >
            COORD: -6.17 / 106.82<br />
            NODES: 1,402,110<br />
            STATUS: SYNCED_LIVE<br />
            BUFFER: REAL-TIME
        </div>
    </div>
</template>

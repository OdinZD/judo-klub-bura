<div>
    @if($sent)
        <flux:callout variant="success" icon="check-circle" class="mb-6">
            <flux:callout.heading>Poruka poslana!</flux:callout.heading>
            <flux:callout.text>Hvala vam na poruci. Javit ćemo vam se u najkraćem mogućem roku.</flux:callout.text>
        </flux:callout>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <div class="grid sm:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Ime i prezime</flux:label>
                <flux:input wire:model="name" placeholder="Vaše ime" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" placeholder="vas@email.com" />
                <flux:error name="email" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Predmet</flux:label>
            <flux:input wire:model="subject" placeholder="O čemu se radi?" />
            <flux:error name="subject" />
        </flux:field>

        <flux:field>
            <flux:label>Poruka</flux:label>
            <flux:textarea wire:model="message" placeholder="Vaša poruka..." rows="5" />
            <flux:error name="message" />
        </flux:field>

        <flux:button type="submit" variant="primary" class="hover:scale-[1.02] transition-transform">
            Pošalji poruku
        </flux:button>
    </form>
</div>

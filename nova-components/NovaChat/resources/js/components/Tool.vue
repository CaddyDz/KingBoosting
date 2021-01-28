<template>
	<div class="chat-resource">
		<div class="font-sans antialiased h-screen flex chat-tool">
			<!-- Chat content -->
			<div class="flex-1 flex flex-col bg-white overflow-hidden card mb-6 py-3 px-6">
				<!-- Top bar -->
				<div class="border-b flex px-6 py-2 items-center flex-none">
					<div class="flex flex-col">
						<h3 class="text-grey-darkest mb-1 font-extrabold">Chat with {{ this.order.client.username }}</h3>
						<div class="text-grey-dark text-sm truncate">Do NOT share your login details in the chat!</div>
					</div>
				</div>
				<!-- Chat messages -->
				<div class="px-6 py-4 flex-1 overflow-y-scroll" ref="chatContent">
					<!-- A message -->
					<div class="flex items-start mb-4 text-sm">
						<div class="flex-1 overflow-hidden">
							<div>
								<span class="font-bold">King Boosting</span>
							</div>
							<p class="text-white leading-normal">
								Thank you for your purchase! You will be able to talk to your
								booster/coach right here!
							</p>
						</div>
					</div>
					<!-- A message -->
					<div class="flex items-start mb-4 text-sm" v-for="message in messages" :key="message.id">
						<div class="flex-1 overflow-hidden">
							<div>
								<span class="font-bold">
									{{
									message.sender || getMessageSenderName(message.sender_id)
									}}
								</span>
								<span class="text-grey text-xs">{{ message.at }}</span>
							</div>
							<p class="text-white leading-normal">{{ message.content }}</p>
						</div>
					</div>
				</div>
				<div class="pb-6 px-4 flex-none">
					<div class="flex rounded-lg border-2 border-grey overflow-hidden">
						<input @keydown.enter.exact.prevent="handleSendMessage" type="text" class="w-full px-4 text-3xl text-grey border-r-2 border-grey p-2" placeholder="Type your message..." v-model="message" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ["resourceName", "resourceId", "panel"],

	data: () => ({
		// I need to know if the auth'd user is a booster or a client
		order: null,
		auth_id: null,
		message: "",
		messages: [],
	}),

	methods: {
		sendMessage() {
			if (this.message == "") {
				return;
			}
			axios
				.post("/messages/send", {
					to:
						this.order.client_id == this.auth_id
							? this.order.booster_id
							: this.order.client_id,
					message: this.message,
					chat: this.chat.id,
				})
				.then((response) => {
					this.messages.push(response.data.message);
					this.scrollToBottom();
				});

			this.message = "";
		},
		scrollToBottom() {
			setTimeout(() => {
				this.$refs.chatContent.scrollTop =
					this.$refs.chatContent.scrollHeight -
					this.$refs.chatContent.clientHeight;
			}, 50);
		},
		handleSendMessage(e) {
			this.sendMessage();
		},
		getMessageSenderName(id) {
			return id === this.order.client_id
				? this.order.client.username
				: this.order.booster.username;
		},
		listenForMessages() {
			window.Echo.private(`channel.messages.${this.auth_id}`).listen(
				"SendMessage",
				(e) => {
					this.messages.push(e.message);
					this.scrollToBottom();
				}
			);
		},
	},

	created() {
		this.order = this.panel.fields[0].order;
		this.chat = this.order.chat;
		this.messages = this.chat.messages;
		this.auth_id = this.panel.fields[0].auth_id;
		this.listenForMessages();
	},
};
</script>

<template>
  <div>
    <div class="font-sans antialiased h-screen flex">
      <!-- Chat content -->
      <div class="flex-1 flex flex-col bg-white overflow-hidden">
        <!-- Top bar -->
        <div class="border-b flex px-6 py-2 items-center flex-none">
          <div class="flex flex-col">
            <h3 class="text-grey-darkest mb-1 font-extrabold">
              Chat with {{ this.order.client.username }}
            </h3>
            <div class="text-grey-dark text-sm truncate">
              Do NOT share your login details in the chat!
            </div>
          </div>
        </div>
        <!-- Chat messages -->
        <div class="px-6 py-4 flex-1 overflow-y-scroll">
          <!-- A message -->
          <div class="flex items-start mb-4 text-sm">
            <div class="flex-1 overflow-hidden">
              <div>
                <span class="font-bold">Steve Schoger</span>
                <span class="text-grey text-xs">11:46</span>
              </div>
              <p class="text-white leading-normal">
                The slack from the other side.
              </p>
            </div>
          </div>
          <!-- A message -->
          <div class="flex items-start mb-4 text-sm">
            <div class="flex-1 overflow-hidden">
              <div>
                <span class="font-bold">Adam Wathan</span>
                <span class="text-grey text-xs">12:45</span>
              </div>
              <p class="text-white leading-normal">
                How are we supposed to control the marquee space without an
                utility for it? I propose this:
              </p>
            </div>
          </div>
        </div>
        <div class="pb-6 px-4 flex-none">
          <div class="flex rounded-lg border-2 border-grey overflow-hidden">
            <input
              type="text"
              class="w-full px-4 text-3xl text-grey border-r-2 border-grey p-2"
              placeholder="Type your message..."
            />
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
    order: null,
  }),

  mounted() {
    this.order = this.panel.fields[0].order;
  },
};
</script>

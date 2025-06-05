<template>
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="text-h6">المنتور</v-list-item-title>
      </v-list-item-content>
      <v-btn icon @click="$emit('close')">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-list-item>
    <v-divider></v-divider>
    <v-list>
      <v-list-item v-for="element in elements" :key="element.id" @click="$emit('add-element', element)">
        <v-list-item-icon>
          <v-icon>{{ element.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>{{ element.name }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </template>
  
  <script setup>
  const props = defineProps({
    elements: {
      type: Array,
      required: true
    }
  })
  </script>
  
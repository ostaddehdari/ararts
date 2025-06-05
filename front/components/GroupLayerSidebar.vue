<script setup>
import { ref } from 'vue'
const props = defineProps({ addLayer: Function })
const groupName = ref('')
function addGroupLayer() {
  if (!groupName.value) return alert('نام گروه را وارد کنید!')
  props.addLayer({
    name: groupName.value,
    type: 'group'
  })
  groupName.value = ''
}
</script> 
import { reactive } from 'vue'
import { set } from "lodash";

export function useFormNested() {

  let errors = reactive({});

  const setErrors = (serverErrors) => {

    for (const [key, value] of Object.entries(serverErrors)) {
      console.log(`${key}: ${value}`);
      set(errors, key, value);
    }
  }

  // const initErrors = (obj) => {

  //   for (const [key, value] of Object.entries(obj)) {

  //     errors[key] = null;
  //   }
  // }


  return { errors, setErrors }
}